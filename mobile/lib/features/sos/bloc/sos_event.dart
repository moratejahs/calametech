part of 'sos_bloc.dart';

@immutable
sealed class SosEvent extends Equatable {
  const SosEvent();

  @override
  List<Object> get props => [];
}

class SOSRequested extends SosEvent {
  final double lat;
  final double long;

  const SOSRequested({
    required this.lat,
    required this.long,
  });

  @override
  List<Object> get props => [lat, long];
}