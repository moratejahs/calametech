part of 'tips_bloc.dart';

@immutable
sealed class TipsEvent extends Equatable {
  const TipsEvent();

  @override
  List<Object> get props => [];
}

final class TipsFetched extends TipsEvent {}
