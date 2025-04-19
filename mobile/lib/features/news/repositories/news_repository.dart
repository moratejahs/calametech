import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/news/models/news.dart';
import 'package:http/http.dart' as http;

class NewsRepository {
  final http.Client httpClient;

  NewsRepository({
    required this.httpClient,
  });

  Future<List<News>> getNews(String token) async {
    final response = await httpClient.get(
      Uri.parse(ApiPaths.news),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body)['data'];
      return data.map((json) => News.fromJson(json)).toList();
    } else {
      throw Exception('Failed to fetch news.');
    }
  }
}
