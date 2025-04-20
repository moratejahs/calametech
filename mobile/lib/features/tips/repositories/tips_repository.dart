import 'dart:convert';

import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/features/tips/models/tip_model.dart';
import 'package:calamitech/features/tips/repositories/i_tips_repository.dart';
import 'package:http/http.dart' as http;

class TipsRepository extends ITipsRepository {
  final http.Client httpClient;
  final String token;

  TipsRepository({
    required this.httpClient,
    required this.token,
  });

  @override
  Future<List<TipModel>> getTips() async {
    final response = await httpClient.post(
      Uri.parse(ApiPaths.aiApiUrl),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'model': 'gpt-3.5-turbo',
        'messages': [
          {
            'role': 'system',
            'content': """
You are an AI assistant that provides safety tips. The response should be a JSON array of tips, where each tip has the following structure:

{
  "content": "Tip content here",
  "type": "fire_tips" | "flood_tips" | "safety_tips"
}

Ensure that the response is a valid JSON array containing multiple tips under different categories.
"""
          },
          {
            'role': 'user',
            'content': """
Provide a JSON array response of safety tips categorized under "fire_tips", "flood_tips", and "safety_tips". Each tip should contain 5 to 10 tips maximum and follow this structure:

{
  "content": "Tip content here",
  "type": "fire_tips" | "flood_tips" | "safety_tips"
}
"""
          },
        ],
        'max_tokens': 1000,
      }),
    );

    if (response.statusCode == 200) {
      final responseBody = json.decode(response.body);
      final content =
          responseBody['choices'][0]['message']['content'] as String;

      try {
        final cleanedContent =
            content.replaceAll('```json', '').replaceAll('```', '').trim();
        final parsedTips = jsonDecode(cleanedContent);

        if (parsedTips is List) {
          return parsedTips.map((tip) => TipModel.fromJson(tip)).toList();
        } else {
          throw Exception('Received invalid tips format.');
        }
      } catch (e) {
        throw Exception('Failed to parse tips.');
      }
    } else {
      throw Exception('Failed to fetch tips.');
    }
  }

  @override
  Future<List<TipModel>> getStoredTips() {
    // TODO: implement getStoredTips
    throw UnimplementedError();
  }
}
