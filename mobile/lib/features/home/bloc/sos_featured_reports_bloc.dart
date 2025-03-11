import 'package:bloc/bloc.dart';
import 'package:calamitech/core/auth/login/models/user.dart';
import 'package:calamitech/features/home/models/sos_report.dart';
import 'package:calamitech/features/home/repositories/sos_reports_repository.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:meta/meta.dart';

part 'sos_featured_reports_event.dart';
part 'sos_featured_reports_state.dart';

class SosFeaturedReportsBloc extends Bloc<SosFeaturedReportsEvent, SosFeaturedReportsState> {
  final SosReportsRepository sosReportsRepository;
  final SecureStorageService storage;

  SosFeaturedReportsBloc({
    required this.sosReportsRepository,
    required this.storage,
  }) : super(SosFeaturedReportsInitial()) {
    on<SosFeaturedReportsFetched>(_onSosFeaturedReportsFetched);
  }

  Future<void> _onSosFeaturedReportsFetched(SosFeaturedReportsFetched event, Emitter<SosFeaturedReportsState> emit) async {
    try {
      emit(SosFeaturedReportsLoading());

      final authUser = await storage.readValue('user');

      if (authUser == null) {
        throw Exception('Unauthenticated');
      }

      final sosFeaturedReports = await sosReportsRepository.getSosFeaturedReports(User.fromJson(authUser).token);

      emit(SosFeaturedReportsLoaded(sosFeaturedReports));
    } catch (e) {
      emit(SosFeaturedReportsError(e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
