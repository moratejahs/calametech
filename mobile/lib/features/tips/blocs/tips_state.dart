part of 'tips_bloc.dart';

@immutable
sealed class TipsState {}

final class TipsInitial extends TipsState {}

final class TipsLoading extends TipsState {}

final class TipsLoaded extends TipsState {
  final List<TipModel> tips;

  TipsLoaded({
    required this.tips,
  });
}

final class TipsFailure extends TipsState {
  final String message;

  TipsFailure({
    required this.message,
  });
}
