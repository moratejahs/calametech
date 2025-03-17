import 'dart:convert';

import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';

import 'package:calamitech/features/ai_tips/ai_tips.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

part 'tips_event.dart';
part 'tips_state.dart';

class TipsBloc extends Bloc<TipsEvent, TipsState> {
  final TipsRepository tipsRepository;
  final SecureStorageService storage;

  TipsBloc({
    required this.tipsRepository,
    required this.storage,
  }) : super(TipsInitial()) {
    on<TipsFetched>(_onTipsFetched);
  }

  Future<void> _onTipsFetched(TipsFetched event, Emitter<TipsState> emit) async {
    emit(TipsLoading());

    try {
      final String? storedTips = await storage.readValue('tips');

      if (storedTips != null) {
        final Map<String, dynamic> oldTips = jsonDecode(storedTips);
        final List<dynamic> rawTips = oldTips['tips'];

        if (oldTips['date'] == DateTime.now().day) {
          final List<Tip> tips = rawTips.map((tip) {
            if (tip is String) {
              return Tip.fromMap(jsonDecode(tip));
            } else {
              return Tip.fromMap(tip);
            }
          }).toList();

          emit(TipsLoaded(tips));
          return;
        }
      }

      final authUser = await storage.readValue('user');

      if (authUser == null) {
        throw Exception('Unauthenticated');
      }

      final tips = await tipsRepository.fetchTips(dotenv.env['AI_API_KEY'] ?? '');

      await storage.writeValue('tips', jsonEncode({'date': DateTime.now().day, 'tips': tips}));

      emit(TipsLoaded(tips));
    } catch (e) {
      emit(TipsError(e.toString().replaceFirst('Exception: ', '')));
    }
  }
}
