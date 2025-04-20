// import 'dart:convert';
// import 'package:calamitech/constants/api_paths.dart';
// import 'package:calamitech/features/auth/repositories/auth_token_repository.dart';
// import 'package:calamitech/features/news/models/news_model.dart';
// import 'package:calamitech/features/news/repositories/i_news_repository.dart';
// import 'package:calamitech/utils/services/secure_storage_service.dart';
// import 'package:http/http.dart' as http;
//
// class NewsRepository extends INewsRepository {
//   final http.Client httpClient;
//   final AuthTokenRepository authTokenRepository;
//
//   NewsRepository({
//     required this.httpClient,
//     required this.authTokenRepository,
//   });
//
//   @override
//   Future<List<NewsModel>> getNews() async {
//     final String? token = await authTokenRepository.getAuthToken();
//
//     if (token == null) {
//       throw Exception('Unauthenticated.');
//     }
//
//     final response = await httpClient.get(
//       Uri.parse(ApiPaths.news),
//       headers: {
//         'Content-Type': 'application/json',
//         'Accept': 'application/json',
//         'Authorization': 'Bearer $token',
//       },
//     );
//
//     final jsonBody = json.decode(response.body);
//
//     if (response.statusCode == 200) {
//       final List<dynamic> data = jsonBody['data'];
//       return data.map((json) => NewsModel.fromJson(json)).toList();
//     } else {
//       throw Exception(jsonBody['message'] ?? 'Failed to fetch news.');
//     }
//   }
// }
