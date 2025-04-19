import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/news/models/news_model.dart';
import 'package:calamitech/features/news/repositories/i_news_repository.dart';
import 'package:http/http.dart' as http;

class NewsRepository extends INewsRepository {
  final http.Client httpClient;

  NewsRepository({
    required this.httpClient,
  });

  @override
  Future<List<NewsModel>> getNews(String token) async {
    final response = await httpClient.get(
      Uri.parse(ApiPaths.news),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode == 200) {
      final List<dynamic> data = jsonBody['data'];
      return data.map((json) => NewsModel.fromJson(json)).toList();
    } else {
      throw Exception(jsonBody['message'] ?? 'Failed to fetch news.');
    }
  }
}
