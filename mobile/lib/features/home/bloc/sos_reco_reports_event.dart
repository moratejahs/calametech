part of 'sos_reco_reports_bloc.dart';

@immutable
sealed class SosRecoReportsEvent extends Equatable {
  const SosRecoReportsEvent();

  @override
  List<Object> get props => [];
}

final class SosRecoReportsFetched extends SosRecoReportsEvent {}
