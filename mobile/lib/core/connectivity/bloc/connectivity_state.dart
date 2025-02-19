part of 'connectivity_bloc.dart';

@immutable
sealed class ConnectivityState extends Equatable {
  const ConnectivityState();

  @override
  List<Object> get props => [];
}

class ConnectivityInitial extends ConnectivityState {}

class ConnectivitySuccess extends ConnectivityState {
  final List<ConnectivityResult> connectivityResults;

  const ConnectivitySuccess(this.connectivityResults);

  @override
  List<Object> get props => [connectivityResults];
}

class ConnectivityFailure extends ConnectivityState {
  final String error;

  const ConnectivityFailure(this.error);

  @override
  List<Object> get props => [error];
}
