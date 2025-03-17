part of 'tips_bloc.dart';

@immutable
sealed class TipsState extends Equatable {
  const TipsState();
}

final class TipsInitial extends TipsState {
  @override
  List<Object> get props => [];
}

final class TipsLoading extends TipsState {
  @override
  List<Object> get props => [];
}

final class TipsLoaded extends TipsState {
  final List<Tip> tips;

  const TipsLoaded(
    this.tips,
  );

  @override
  List<Object> get props => [tips];
}

final class TipsError extends TipsState {
  final String message;

  const TipsError(this.message);

  @override
  List<Object> get props => [
        message,
      ];
}
