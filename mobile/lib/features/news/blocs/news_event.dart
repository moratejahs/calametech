part of 'news_bloc.dart';

@immutable
sealed class NewsEvent {}

final class NewsFetched extends NewsEvent {
  final String token;

  NewsFetched({
    required this.token,
  });
}