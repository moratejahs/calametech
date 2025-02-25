import 'package:calamitech/features/sos/models/sos.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter/foundation.dart';
import 'package:equatable/equatable.dart';

import '../../../utils/services/secure_storage_service.dart';
import '../repositories/sos_repository.dart';

part 'sos_event.dart';
part 'sos_state.dart';

class SosBloc extends Bloc<SosEvent, SosState> {
  final SOSRepository sosRepository;
  final SecureStorageService storage;

  SosBloc({required this.sosRepository, required this.storage}) : super(SosInitial()) {
    on<SOSRequested>(_onSOSRequested);
  }

  Future<void> _onSOSRequested(SOSRequested event, Emitter<SosState> emit) async {
    try {
      emit(SosLoading());

      debugPrint('in bloc lat: ${event.lat}, long: ${event.long}');

      final response = await sosRepository.sendSOS(event.lat, event.long);

      debugPrint('in bloc response: $response');

      if (response.containsKey('errors')) {
        emit(SosFailure(errors: response['errors']));
        return;
      }

      final sos =  SOS.fromMap(response['sos']);
      debugPrint('in bloc sos to json: ${sos.toJson()}');
      final success = await storage.writeValue('sos', sos.toJson());

      if (!success) {
        emit(const SosFailure(message: 'Failed to save SOS'));
        return;
      }

      emit(SosSuccess(sos));
      return;
    } catch (e) {
      emit(SosFailure(message: e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
