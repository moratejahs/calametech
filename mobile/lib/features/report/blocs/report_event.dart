part of 'report_bloc.dart';

@immutable
sealed class ReportEvent {}

final class ReportSubmitted extends ReportEvent {
  final String emergencyType;
  final String description;
  final File? image;
  final String long;
  final String lat;

  ReportSubmitted({
    required this.emergencyType,
    required this.description,
    required this.image,
    required this.lat,
    required this.long,
  });
}
