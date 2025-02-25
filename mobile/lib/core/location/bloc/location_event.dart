part of 'location_bloc.dart';

@immutable
sealed class LocationEvent {}

final class LocationStarted extends LocationEvent {}

final class LocationStopped extends LocationEvent {}