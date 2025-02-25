part of 'location_bloc.dart';

@immutable
sealed class LocationState {}

final class LocationInitial extends LocationState {}

final class LocationLoading extends LocationState {}

final class LocationSuccess extends LocationState {
  final double lat;
  final double long;

  LocationSuccess({
    required this.lat,
    required this.long,
  });
}

final class LocationError extends LocationState {
  final String message;

  LocationError({
    required this.message,
  });
}
