// import 'package:bloc/bloc.dart';
// import 'package:flutter/material.dart';
// import 'package:equatable/equatable.dart';
// import 'package:calamitech/utils/services/secure_storage_service.dart';
// import 'package:calamitech/features/sos_reports/sos_reports.dart';
// import 'package:calamitech/core/auth/login/models/user.dart';
//
// part 'sos_reco_reports_event.dart';
// part 'sos_reco_reports_state.dart';
//
// class SosRecoReportsBloc extends Bloc<SosRecoReportsEvent, SosRecoReportsState> {
//   final SosReportsRepository sosReportsRepository;
//   final SecureStorageService storage;
//
//   SosRecoReportsBloc({
//     required this.sosReportsRepository,
//     required this.storage,
//   }) : super(SosRecoReportsInitial()) {
//     on<SosRecoReportsFetched>(_onSosRecoReportsFetched);
//   }
//
//   Future<void> _onSosRecoReportsFetched(SosRecoReportsFetched event, Emitter<SosRecoReportsState> emit) async {
//     try {
//       emit(SosRecoReportsLoading());
//
//       final authUser = await storage.readValue('user');
//
//       if (authUser == null) {
//         throw Exception('Unauthenticated');
//       }
//
//       final sosReports = await sosReportsRepository.getSosRecoReports(User.fromJson(authUser).token);
//
//       emit(SosRecoReportsLoaded(sosReports));
//     } catch (e) {
//       emit(SosRecoReportsError(e.toString().replaceFirst('Exception: ', '')));
//     }
//   }
// }
