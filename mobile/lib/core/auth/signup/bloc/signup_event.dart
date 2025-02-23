part of 'signup_bloc.dart';

@immutable
sealed class SignupEvent extends Equatable {
  const SignupEvent();

  @override
  List<Object> get props => [];
}

class SignupRequested extends SignupEvent {
  final String name;
  final String email;
  final String password;
  final String confirmPassword;

  const SignupRequested({
    required this.name,
    required this.email,
    required this.password,
    required this.confirmPassword,
  });

  @override
  List<Object> get props => [name, email, password, confirmPassword];
}
