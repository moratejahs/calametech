part of 'auth_bloc.dart';

@immutable
sealed class AuthEvent {}

final class LoginRequested extends AuthEvent {
  final String email;
  final String password;

  LoginRequested({
    required this.email,
    required this.password,
  });
}

final class RegisterRequested extends AuthEvent {
  final String name;
  final String email;
  final String password;
  final String passwordConfirmation;
  final String phone;
  final String address;
  final File avatar;
  final File idPicture;
  final String idType;

  RegisterRequested({
    required this.name,
    required this.email,
    required this.password,
    required this.passwordConfirmation,
    required this.phone,
    required this.address,
    required this.avatar,
    required this.idPicture,
    required this.idType,
  });
}

final class LogoutRequested extends AuthEvent {
  LogoutRequested();
}

final class AuthCheckRequested extends AuthEvent {}

final class MarkAuthUserAsVerified extends AuthEvent {}
