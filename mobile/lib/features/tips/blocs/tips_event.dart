part of 'tips_bloc.dart';

@immutable
sealed class TipsEvent {}

final class TipsFetched extends TipsEvent {}
