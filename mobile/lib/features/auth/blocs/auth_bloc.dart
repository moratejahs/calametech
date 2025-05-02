import 'dart:io';

import 'package:bloc/bloc.dart';
import 'package:calamitech/core/exceptions/validation_exception.dart';
import 'package:calamitech/core/utils/helpers/remove_exception_prefix.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/auth/models/user_model.dart';
import 'package:calamitech/features/auth/repositories/auth_repository.dart';
import 'package:meta/meta.dart';

part 'auth_event.dart';

part 'auth_state.dart';

class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final AuthRepository authRepository;
  final AuthUserService authUserService;

  AuthBloc({
    required this.authRepository,
    required this.authUserService,
  }) : super(AuthInitial()) {
    on<LoginRequested>(onLoginRequested);
    on<RegisterRequested>(onRegisterRequested);
    on<LogoutRequested>(onLogoutRequested);
    on<AuthCheckRequested>(onAuthCheckRequested);
  }

  Future<void> onLoginRequested(LoginRequested event, Emitter<AuthState> emit) async {
    emit(AuthLoading());

    try {
      final user = await authRepository.login(
        event.email,
        event.password,
      );

      if (!await authUserService.store(user)) {
        throw Exception('Failed to store user.');
      }

      emit(AuthAuthenticated(user: user));
    } on ValidationException catch (e) {
      emit(
        AuthLoginFieldError(
          emailError: e.errors['email']?.first ?? '',
          passwordError: e.errors['password']?.first ?? '',
        ),
      );
    } catch (e) {
      emit(AuthFailure(message: removeExceptionPrefix(e.toString())));
    }
  }

  Future<void> onRegisterRequested(RegisterRequested event, Emitter<AuthState> emit) async {
    emit(AuthLoading());

    try {
      final user = await authRepository.register(
        name: event.name,
        email: event.email,
        password: event.password,
        passwordConfirmation: event.passwordConfirmation,
        phone: event.phone,
        address: event.address,
        avatar: event.avatar,
        idPicture: event.idPicture,
        idType: event.idType,
      );

      if (!await authUserService.store(user)) {
        throw Exception('Failed to store user.');
      }

      emit(AuthAuthenticated(user: user));
    } on ValidationException catch (e) {
      emit(
        AuthRegisterFieldError(
          nameError: e.errors['name']?.first,
          emailError: e.errors['email']?.first,
          passwordError: e.errors['password']?.first,
          passwordConfirmationError: e.errors['password_confirmation']?.first,
          phoneError: e.errors['phone']?.first,
          addressError: e.errors['address']?.first,
          avatarError: e.errors['avatar']?.first,
          idPictureError: e.errors['id_picture']?.first,
          idTypeError: e.errors['id_type']?.first,
        ),
      );
    } catch (e) {
      emit(AuthFailure(message: removeExceptionPrefix(e.toString())));
    }
  }

  Future<void> onLogoutRequested(LogoutRequested event, Emitter<AuthState> emit) async {
    emit(AuthLoading());

    try {
      await authRepository.logout();

      emit(AuthUnAuthenticated());
    } catch (e) {
      emit(AuthFailure(message: removeExceptionPrefix(e.toString())));
    }
  }

  Future<void> onAuthCheckRequested(AuthCheckRequested event, Emitter<AuthState> emit) async {
    emit(AuthLoading());

    try {
      final user = await authUserService.get();

      if (user != null) {
        emit(AuthAuthenticated(user: user));
      } else {
        emit(AuthUnAuthenticated());
      }
    } catch (e) {
      emit(AuthFailure(message: removeExceptionPrefix(e.toString())));
    }
  }
}
