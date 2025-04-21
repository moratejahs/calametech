import 'dart:io';

import 'package:bloc/bloc.dart';
import 'package:calamitech/core/utils/helpers/remove_exception_prefix.dart';
import 'package:calamitech/features/report/repositories/sos_repository.dart';
import 'package:flutter/material.dart';

part 'report_event.dart';

part 'report_state.dart';

class ReportBloc extends Bloc<ReportEvent, ReportState> {
  final SosRepository sosRepository;

  ReportBloc({
    required this.sosRepository,
  }) : super(ReportInitial()) {
    on<ReportSubmitted>(onReportSubmitted);
  }

  Future<void> onReportSubmitted(
      ReportSubmitted event, Emitter<ReportState> emit) async {
    try {
      emit(ReportLoading());

      await sosRepository.store(
        event.description,
        event.emergencyType,
        event.image,
        event.lat,
        event.long,
      );

      emit(ReportSuccess('Report submitted.'));
      return;
    } catch (e) {
      emit(ReportFailure(message: removeExceptionPrefix(e.toString())));
    }
  }
}
