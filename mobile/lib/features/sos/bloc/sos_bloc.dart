// import 'package:calamitech/features/sos/models/sos.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
// import 'package:flutter/foundation.dart';
// import 'package:equatable/equatable.dart';
//
// import '../../../core/auth/login/models/user.dart';
// import '../../../utils/services/secure_storage_service.dart';
// import '../repositories/sos_repository.dart';
//
// part 'sos_event.dart';
//
// part 'sos_state.dart';
//
// class SosBloc extends Bloc<SosEvent, SosState> {
//   final SOSRepository sosRepository;
//   final SecureStorageService storage;
//
//   SosBloc({required this.sosRepository, required this.storage})
//       : super(SosInitial()) {
//     on<SOSRequested>(_onSOSRequested);
//   }
//
//   Future<void> _onSOSRequested(
//       SOSRequested event, Emitter<SosState> emit) async {
//     try {
//       emit(SosLoading());
//
//       final authUser = await storage.readValue('user');
//
//       if (authUser == null) {
//         throw Exception('User is not signed in.');
//       }
//
//       final response = await sosRepository.sendSOS(
//           User.fromJson(authUser).token, event.lat, event.long);
//
//       if (response.containsKey('errors')) {
//         emit(SosFailure(errors: response['errors']));
//         return;
//       }
//
//       final sos = SOS.fromMap(response['sos']);
//
//       final sosStore = await storage.writeValue('sos', sos.toJson());
//
//       if (!sosStore) {
//         throw Exception('Failed to save SOS');
//       }
//
//       emit(SosSuccess(sos));
//       return;
//     } catch (e) {
//       emit(SosFailure(message: e.toString().replaceFirst('Exception: ', '')));
//     }
//   }
// }
