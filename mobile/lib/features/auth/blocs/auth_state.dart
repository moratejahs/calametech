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
