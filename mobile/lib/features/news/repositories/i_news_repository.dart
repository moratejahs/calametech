import 'package:calamitech/features/news/models/news_model.dart';

abstract class INewsRepository {
  Future<List<NewsModel>> getNews();
}