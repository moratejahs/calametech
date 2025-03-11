part of 'sos_featured_reports_bloc.dart';

@immutable
sealed class SosFeaturedReportsState extends Equatable {
  const SosFeaturedReportsState();

  @override
  List<Object> get props => [];
}

final class SosFeaturedReportsInitial extends SosFeaturedReportsState {}

final class SosFeaturedReportsLoading extends SosFeaturedReportsState {}

final class SosFeaturedReportsLoaded extends SosFeaturedReportsState {
  final List<SosReport> sosFeaturedReports;

  const SosFeaturedReportsLoaded(this.sosFeaturedReports);

  @override
  List<Object> get props => [sosFeaturedReports];
}

final class SosFeaturedReportsError extends SosFeaturedReportsState {
  final String message;

  const SosFeaturedReportsError(this.message);

  @override
  List<Object> get props => [message];
}
