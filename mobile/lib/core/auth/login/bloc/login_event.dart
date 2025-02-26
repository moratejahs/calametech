part of 'login_bloc.dart';

@immutable
sealed class LoginEvent extends Equatable {
  const LoginEvent();

  @override
  List<Object> get props => [];
}

class LoginButtonPressed extends LoginEvent {
  final String email;
  final String password;

  const LoginButtonPressed({
    required this.email,
    required this.password,
  });

  @override
  List<Object> get props => [email, password];
}

class LogoutButtonPressed extends LoginEvent {
  const LogoutButtonPressed();

  @override
  List<Object> get props => [];
}

class UserAlreadyLoggedIn extends LoginEvent {
  final User user;

  const UserAlreadyLoggedIn(this.user);

  @override
  List<Object> get props => [user];
}
