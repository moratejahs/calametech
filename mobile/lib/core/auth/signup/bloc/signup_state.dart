part of 'signup_bloc.dart';

@immutable
sealed class SignupState extends Equatable {
  const SignupState();

  @override
  List<Object> get props => [];
}

final class SingupInitial extends SignupState {}

final class SignupLoading extends SignupState {}

class SignupSuccess extends SignupState {
  final String message;
  const SignupSuccess({
    required this.message,
  });

  @override
  List<Object> get props => [
        message,
      ];
}

class SignupFailure extends SignupState {
  final String? message;
  final Map<String, dynamic>? errors;

  const SignupFailure({
    this.message,
    this.errors,
  });

  @override
  List<Object> get props => [
        if (message != null) message!,
        if (errors != null) errors!,
      ];
}

class LogoutSuccess extends SignupState {}
