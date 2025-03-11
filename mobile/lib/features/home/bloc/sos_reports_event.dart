part of 'sos_reports_bloc.dart';

sealed class SosReportsEvent extends Equatable {
  const SosReportsEvent();

  @override
  List<Object> get props => [];
}

final class SosReportsFetched extends SosReportsEvent {}
