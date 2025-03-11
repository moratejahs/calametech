import 'package:bloc/bloc.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:equatable/equatable.dart';

import '../../../core/auth/login/models/user.dart';
import '../home.dart';

part 'sos_reports_event.dart';
part 'sos_reports_state.dart';

class SosReportsBloc extends Bloc<SosReportsEvent, SosReportsState> {
  final SosReportsRepository sosReportsRepository;
  final SecureStorageService storage;

  SosReportsBloc({
    required this.sosReportsRepository,
    required this.storage,
  }) : super(SosReportsInitial()) {
    on<SosReportsFetched>(_onSosReportsFetched);
  }

  Future<void> _onSosReportsFetched(SosReportsFetched event, Emitter<SosReportsState> emit) async {
    try {
      emit(SosReportsLoading());

      final authUser = await storage.readValue('user');

      if (authUser == null) {
        throw Exception('Unauthenticated');
      }

      final sosReports = await sosReportsRepository.getSosReports(User.fromJson(authUser).token);

      emit(SosReportsLoaded(sosReports));
    } catch (e) {
      emit(SosReportsError(e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
