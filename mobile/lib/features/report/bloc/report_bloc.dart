// import 'dart:io';
//
// import 'package:bloc/bloc.dart';
// import 'package:calamitech/core/auth/login/models/user.dart';
// import 'package:calamitech/features/report/report.dart';
// import 'package:calamitech/features/sos/models/sos.dart';
// import 'package:calamitech/utils/services/secure_storage_service.dart';
// import 'package:equatable/equatable.dart';
// import 'package:flutter/material.dart';
//
// part 'report_event.dart';
// part 'report_state.dart';
//
// class ReportBloc extends Bloc<ReportEvent, ReportState> {
//   final ReportRepository reportRepository;
//
//   ReportBloc({
//     required this.reportRepository,
//   }) : super(ReportInitial()) {
//     on<ReportSubmitted>(_onReportSubmitted);
//   }
//
//   Future<void> _onReportSubmitted(ReportSubmitted event, Emitter<ReportState> emit) async {
//     try {
//       emit(ReportLoading());
//
//       final sosData = await storage.readValue('sos');
//       final sos = SOS.fromJson(sosData);
//       if (sosData == null) {
//         throw Exception('SOS is not available.');
//       }
//       final authUser = await storage.readValue('user');
//       if (authUser == null) {
//         throw Exception('User is not signed in.');
//       }
//
//       final response = await reportRepository.submitReport(
//         sos.id,
//         event.description,
//         event.emergencyType,
//         event.image,
//         User.fromJson(authUser).token,
//       );
//
//       if (response.containsKey('error')) {
//         emit(ReportError(errors: response['error']));
//         return;
//       }
//
//       emit(ReportSuccess(
//         response['success'],
//       ));
//
//       return;
//     } catch (e) {
//       emit(ReportError(message: e.toString().replaceFirst('Exception: ', '')));
//     }
//   }
// }
