part of 'report_bloc.dart';

@immutable
sealed class ReportState {}

final class ReportInitial extends ReportState {}

final class ReportLoading extends ReportState {}

final class ReportSuccess extends ReportState {
  final String message;

  ReportSuccess(this.message);
}

final class ReportFailure extends ReportState {
  final String message;

  ReportFailure({
    required this.message,
  });
}
