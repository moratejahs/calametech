part of 'report_bloc.dart';

@immutable
sealed class ReportEvent extends Equatable {
  const ReportEvent();

  @override
  List<Object> get props => [];
}

final class ReportSubmitted extends ReportEvent {
  final String emergencyType;
  final String description;
  final File? image;

  const ReportSubmitted({
    required this.emergencyType,
    required this.description,
    required this.image,
  });

  @override
  List<Object> get props => [
        emergencyType,
        description,
        image!,
      ];
}
