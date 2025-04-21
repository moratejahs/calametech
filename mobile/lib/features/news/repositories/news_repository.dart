import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/news/models/news_model.dart';
import 'package:calamitech/features/news/repositories/i_news_repository.dart';
import 'package:http/http.dart' as http;

class NewsRepository extends INewsRepository {
  final http.Client httpClient;
  final AuthUserService authUserService;

  NewsRepository({
    required this.httpClient,
    required this.authUserService,
  });

  @override
  Future<List<NewsModel>> getNews() async {
    print('Fetching news...');
    final user = await authUserService.get();

    if (user == null) {
      throw Exception('Unauthenticated.');
    }

    final response = await httpClient.get(
      Uri.parse(ApiPaths.news),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ${user.token}',
      },
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode == 200) {
      final List<dynamic> data = jsonBody['data'];
      final newsList = (data)
          .map((json) => NewsModel.fromMap(json))
          .toList();
      return newsList;
    } else {
      throw Exception(jsonBody['message'] ?? 'Failed to fetch news.');
    }
  }
}
