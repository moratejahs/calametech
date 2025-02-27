import 'dart:async';
import 'package:calamitech/core/auth/login/models/user.dart';
import 'package:calamitech/core/auth/login/repositories/login_repository.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';

part 'login_event.dart';

part 'login_state.dart';

class LoginBloc extends Bloc<LoginEvent, LoginState> {
  final LoginRepository loginRepository;
  final SecureStorageService storage;

  LoginBloc({required this.loginRepository, required this.storage})
      : super(LoginInitial()) {
    on<LoginButtonPressed>(_onLoginButtonPressed);
    on<LogoutButtonPressed>(_onLogoutButtonPressed);
    on<UserAlreadyLoggedIn>(_onUserAlreadyLoggedIn);
  }

  Future<void> _onLoginButtonPressed(
      LoginButtonPressed event, Emitter<LoginState> emit) async {
    try {
      emit(LoginLoading());

      final response = await loginRepository.login(event.email, event.password);
      debugPrint('LoginBloc: login response: $response');

      if (response.containsKey('errors')) {
        emit(LoginFailure(errors: response['errors']));
        return;
      }

      if (response.containsKey('user')) {
        final user = response['user'] as User;

        final authUser = await storage.writeValue('user', user.toJson());

        if (!authUser) {
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

  void _onUserAlreadyLoggedIn(
      UserAlreadyLoggedIn event, Emitter<LoginState> emit) {
    emit(LoginSuccess(event.user));
    return;
  }

  Future<void> _onLogoutButtonPressed(
      LogoutButtonPressed event, Emitter<LoginState> emit) async {
    try {
      emit(SignoutLoading());

      final authUser = await storage.readValue('user');

      if (authUser == null) {
        emit(LoginInitial());
        return;
      }

      final response =
          await loginRepository.logout(User.fromJson(authUser).token);

      if (!response) {
        emit(const SignoutFailure());
        return;
      }

      await storage.deleteValue('user');

      emit(LoginInitial());
      return;
    } catch (e) {
      emit(LoginFailure(message: e.toString().replaceFirst('Exception: ', '')));
    }
  }

  @override
  void onChange(Change<LoginState> change) {
    super.onChange(change);

    debugPrint('LoginBloc: $change');
  }
}
