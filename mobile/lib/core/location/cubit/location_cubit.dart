import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:location/location.dart';

// State
class LocationCubitState extends Equatable {
  final double latitude;
  final double longitude;
  final String? error;

  const LocationCubitState({this.latitude = 0.0, this.longitude = 0.0, this.error});

  @override
  List<Object?> get props => [latitude, longitude, error];
}

// Cubit
class LocationCubit extends Cubit<LocationCubitState> {
  final Location _location = Location();

  LocationCubit() : super(const LocationCubitState());

  // Make this method public
  void startLocationUpdates() async {
    bool serviceEnabled = await _location.serviceEnabled();
    if (!serviceEnabled) {
      serviceEnabled = await _location.requestService();
      if (!serviceEnabled) {
        emit(const LocationCubitState(error: 'Location services are disabled.'));
        return;
      }
    }

    PermissionStatus permissionGranted = await _location.hasPermission();
    if (permissionGranted == PermissionStatus.denied) {
      permissionGranted = await _location.requestPermission();
      if (permissionGranted != PermissionStatus.granted) {
        emit(const LocationCubitState(error: 'Location permission denied.'));
        return;
      }
    }

    _location.onLocationChanged.listen((LocationData currentLocation) {
      emit(LocationCubitState(
        latitude: currentLocation.latitude ?? 0.0,
        longitude: currentLocation.longitude ?? 0.0,
      ));
    });
  }

  void stopLocationUpdates() async {
    _location.onLocationChanged.listen((LocationData currentLocation) {
      emit(const LocationCubitState());
    });
  }
}
