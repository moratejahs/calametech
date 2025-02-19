part of 'login_bloc.dart';

@immutable
sealed class LoginState extends Equatable {
  const LoginState();

  @override
  List<Object> get props => [];
}

class LoginInitial extends LoginState {}

class LoginLoading extends LoginState {}

class LoginSuccess extends LoginState {
  final User user;
  const LoginSuccess(this.user);

  @override
  List<Object> get props => [user];
}

class LoginFailure extends LoginState {
  final String? message;
  final Map<String, dynamic>? errors;

  const LoginFailure({
    this.message,
    this.errors,
  });

  @override
  List<Object> get props => [
        if (message != null) message!,
        if (errors != null) errors!,
      ];
}

class LogoutSuccess extends LoginState {}
