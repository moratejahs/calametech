import 'package:bloc/bloc.dart';
import 'package:calamitech/features/news/models/news.dart';
import 'package:calamitech/features/news/repositories/news_repository.dart';
import 'package:meta/meta.dart';

part 'news_event.dart';

part 'news_state.dart';

class NewsBloc extends Bloc<NewsEvent, NewsState> {
  final NewsRepository newsRepository;

  NewsBloc({required this.newsRepository}) : super(NewsInitial()) {
    on<NewsFetched>(onNewsFetched);
  }

  Future<void> onNewsFetched(NewsFetched event, Emitter<NewsState> emit) async {
    try {
      final newsList = await newsRepository.getNews(event.token);

      emit(NewsLoaded(newsList: newsList));
    } catch (e) {
      emit(NewsFailure(message: e.toString()));
    }
  }
}
