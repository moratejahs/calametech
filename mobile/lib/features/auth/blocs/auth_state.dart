part of 'auth_bloc.dart';

@immutable
sealed class AuthState {}

final class AuthInitial extends AuthState {}

final class AuthLoading extends AuthState {}

final class AuthAuthenticated extends AuthState {
  final UserModel user;

  AuthAuthenticated({required this.user});
}

final class AuthUnAuthenticated extends AuthState {}

final class AuthFailure extends AuthState {
  final String message;

  AuthFailure({required this.message});
}

final class AuthLoginFieldError extends AuthState {
  final String emailError;
  final String passwordError;

  AuthLoginFieldError({
    required this.emailError,
    required this.passwordError,
  });
}

final class AuthRegisterFieldError extends AuthState {
  final String? nameError;
  final String? emailError;
  final String? passwordError;
  final String? passwordConfirmationError;
  final String? phoneError;
  final String? addressError;
  final String? avatarError;
  final String? idPictureError;
  final String? idTypeError;

  AuthRegisterFieldError({
    required this.nameError,
    required this.emailError,
    required this.passwordError,
    required this.passwordConfirmationError,
    required this.phoneError,
    required this.addressError,
    required this.avatarError,
    required this.idPictureError,
    required this.idTypeError,
  });
}
