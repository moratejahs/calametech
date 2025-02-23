import 'dart:async';
import 'package:calametech/core/auth/login/models/user.dart';
import 'package:calametech/core/auth/login/repositories/login_repository.dart';
import 'package:calametech/utils/services/secure_storage_service.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';

part 'login_event.dart';
part 'login_state.dart';

class LoginBloc extends Bloc<LoginEvent, LoginState> {
  final LoginRepository loginRepository;
  final SecureStorageService storage;

  LoginBloc({required this.loginRepository, required this.storage}) : super(LoginInitial()) {
    on<LoginButtonPressed>(_onLoginButtonPressed);
    on<LogoutButtonPressed>(_onLogoutButtonPressed);
  }

  Future<void> _onLoginButtonPressed(LoginButtonPressed event, Emitter<LoginState> emit) async {
    try {
      emit(LoginLoading());

      final response = await loginRepository.login(event.email, event.password);
      debugPrint('response: $response');

      if (response.containsKey('errors')) {
        emit(LoginFailure(errors: response['errors']));
        return;
      }

      if (response.containsKey('user')) {
        final user = response['user'] as User;

        final success = await storage.writeValue('token', user.token);

        if (!success) {
          emit(const LoginFailure(message: 'Failed to save token'));
          return;
        }

        emit(LoginSuccess(user));
        return;
      }

      throw Exception('Unexpected response');
    } catch (e) {
      emit(LoginFailure(message: e.toString().replaceFirst('Exception: ', '')));
    }
  }

  Future<void> _onLogoutButtonPressed(LogoutButtonPressed event, Emitter<LoginState> emit) async {
    try {
      emit(SignoutLoading());

      final token = await storage.readValue('token');

      if (token == null) {
        emit(LoginInitial());
        return;
      }

      final response = await loginRepository.logout(token);

      if (!response) {
        emit(const SignoutFailure());
        return;
      }

      emit(LoginInitial());
    } catch (e) {
      emit(LoginFailure(message: e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
