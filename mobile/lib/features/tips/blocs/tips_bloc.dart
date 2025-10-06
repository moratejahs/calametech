import 'package:bloc/bloc.dart';
import 'package:meta/meta.dart';
import 'package:calamitech/core/utils/helpers/remove_exception_prefix.dart';
import 'package:calamitech/features/tips/models/tip_model.dart';
import 'package:calamitech/features/tips/repositories/tips_repository.dart';

part 'tips_event.dart';

part 'tips_state.dart';

class TipsBloc extends Bloc<TipsEvent, TipsState> {
  final TipsRepository tipsRepository;

  TipsBloc({
    required this.tipsRepository,
  }) : super(TipsInitial()) {
    on<TipsFetched>(onTipsFetched);
  }

  Future<void> onTipsFetched(TipsFetched event, Emitter<TipsState> emit) async {
    emit(TipsLoading());

    try {
      // If the caller provided a description, always fetch fresh tips
      // generated from that description. Otherwise use cached stored tips
      // when available for the current day.
      if (event.description != null && event.description!.trim().isNotEmpty) {
        final tips = await tipsRepository.getTips(event.description);
        emit(TipsLoaded(tips: tips));
        return;
      }

      final storedTips = await tipsRepository.getStoredTips();

      if (storedTips.isNotEmpty &&
          storedTips[0].createdAt?.day == DateTime.now().day) {
        emit(TipsLoaded(tips: storedTips));
      } else {
        final tips = await tipsRepository.getTips();
        emit(TipsLoaded(tips: tips));
      }
    } catch (e) {
      emit(TipsFailure(message: removeExceptionPrefix(e.toString())));
    }
  }
}
