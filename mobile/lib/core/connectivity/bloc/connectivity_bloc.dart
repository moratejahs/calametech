import 'dart:async';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/services.dart';
import 'package:http/http.dart' as http;
import 'package:meta/meta.dart';

part 'connectivity_event.dart';
part 'connectivity_state.dart';

class ConnectivityBloc extends Bloc<ConnectivityEvent, ConnectivityState> {
  final Connectivity _connectivity = Connectivity();
  late StreamSubscription<List<ConnectivityResult>> _connectivitySubscription;

  ConnectivityBloc() : super(ConnectivityInitial()) {
    on<ConnectivityChanged>(_onConnectivityChanged);

    _connectivitySubscription = _connectivity.onConnectivityChanged.listen((result) {
      add(ConnectivityChanged(result));
    });

    _initConnectivity();
  }

  Future<void> _initConnectivity() async {
    try {
      final result = await _connectivity.checkConnectivity();
      add(ConnectivityChanged(result));
    } on PlatformException catch (e) {
      addError('Couldn\'t check connectivity status', e.stacktrace as StackTrace);
    }
  }

  Future<bool> hasInternetConnection() async {
    try {
      final response = await http.get(Uri.parse('https://google.com'));
      return response.statusCode == 200;
    } catch (_) {
      return false;
    }
  }

  FutureOr<void> _onConnectivityChanged(ConnectivityChanged event, Emitter<ConnectivityState> emit) async {
    if (event.connectivityResults.contains(ConnectivityResult.none) || !await hasInternetConnection()) {
      emit(const ConnectivityFailure('No internet connection'));
    } else {
      emit(ConnectivitySuccess(event.connectivityResults));
    }
  }

  @override
  Future<void> close() {
    _connectivitySubscription.cancel();
    return super.close();
  }
}
