part of 'sos_reco_reports_bloc.dart';

@immutable
sealed class SosRecoReportsState extends Equatable {
  const SosRecoReportsState();

  @override
  List<Object> get props => [];
}

final class SosRecoReportsInitial extends SosRecoReportsState {}

final class SosRecoReportsLoading extends SosRecoReportsState {}

final class SosRecoReportsLoaded extends SosRecoReportsState {
  final List<SosReport> sosRecoReports;

  const SosRecoReportsLoaded(this.sosRecoReports);

  @override
  List<Object> get props => [sosRecoReports];
}

final class SosRecoReportsError extends SosRecoReportsState {
  final String message;

  const SosRecoReportsError(this.message);

  @override
  List<Object> get props => [message];
}
