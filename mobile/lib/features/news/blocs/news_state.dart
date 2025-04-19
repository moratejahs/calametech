part of 'news_bloc.dart';

@immutable
sealed class NewsState {}

final class NewsInitial extends NewsState {}

final class NewsLoading extends NewsState {}

final class NewsLoaded extends NewsState {
  final List<NewsModel> newsList;

  NewsLoaded({
    required this.newsList,
  });
}

final class NewsFailure extends NewsState {
  final String message;

  NewsFailure({
    required this.message,
  });
}
