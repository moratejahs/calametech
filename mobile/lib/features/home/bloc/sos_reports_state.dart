part of 'sos_reports_bloc.dart';

sealed class SosReportsState extends Equatable {
  const SosReportsState();

  @override
  List<Object> get props => [];
}

final class SosReportsInitial extends SosReportsState {}

final class SosReportsLoading extends SosReportsState {}

final class SosReportsLoaded extends SosReportsState {
  final List<SosReport> sosReports;

  const SosReportsLoaded(this.sosReports);

  @override
  List<Object> get props => [sosReports];
}

final class SosReportsError extends SosReportsState {
  final String message;

  const SosReportsError(this.message);

  @override
  List<Object> get props => [message];
}
