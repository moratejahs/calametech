import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:location/location.dart';

part 'location_event.dart';

part 'location_state.dart';

class LocationBloc extends Bloc<LocationEvent, LocationState> {
  final Location _location = Location();
  StreamSubscription<LocationData>? _locationSubscription;

  LocationBloc() : super(LocationInitial()) {
    on<LocationStarted>(_onLocationStarted);
    on<LocationStopped>(_onLocationStopped);
  }

  Future<void> _onLocationStarted(
      LocationStarted event, Emitter<LocationState> emit) async {
    emit(LocationLoading());

    bool serviceEnabled = await _location.serviceEnabled();
    if (!serviceEnabled) {
      serviceEnabled = await _location.requestService();
      if (!serviceEnabled) {
        emit(LocationError(message: 'Location services are disabled.'));
        return;
      }
    }

    PermissionStatus permissionGranted = await _location.hasPermission();
    if (permissionGranted == PermissionStatus.denied) {
      permissionGranted = await _location.requestPermission();
      if (permissionGranted != PermissionStatus.granted) {
        emit(LocationError(message: 'Location permission denied.'));
        return;
      }
    }

    // Cancel previous subscription if active
    await _locationSubscription?.cancel();

    // Listen to location changes
    _locationSubscription = _location.onLocationChanged.listen(
      (LocationData currentLocation) {
        if (emit.isDone) return;
        emit(LocationSuccess(
          lat: currentLocation.latitude ?? 0.0,
          long: currentLocation.longitude ?? 0.0,
        ));
      },
    );
  }

  Future<void> _onLocationStopped(
      LocationStopped event, Emitter<LocationState> emit) async {
    await _locationSubscription?.cancel();
    emit(LocationInitial());
  }

  @override
  Future<void> close() {
    _locationSubscription?.cancel();
    return super.close();
  }
}
