part of 'news_bloc.dart';

@immutable
sealed class NewsEvent {}

final class NewsFetched extends NewsEvent {
  NewsFetched();
}
