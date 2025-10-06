import 'dart:convert';

import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/utils/services/tips_service.dart';
import 'package:calamitech/features/tips/models/tip_model.dart';
import 'package:calamitech/features/tips/repositories/i_tips_repository.dart';
import 'package:http/http.dart' as http;

class TipsRepository extends ITipsRepository {
  final http.Client httpClient;
  final String token;
  final TipsService tipsService;

  TipsRepository({
    required this.httpClient,
    required this.token,
    required this.tipsService,
  });

  @override
  Future<List<TipModel>> getTips([String? description]) async {
    // Build the user prompt. If a description is provided, ask the model to
    // generate concise tips based strictly on that description.
    final userPrompt = description != null && description.trim().isNotEmpty
        ? '''Generate up to 3 short safety tips strictly based on the following incident description. Return a raw JSON array (no markdown, no extra text). Each item must be an object with fields: "content" and "type" (one of "fire_tips","flood_tips","safety_tips","other_tips"). The description: "${description.trim()}". Keep tips concise (max 120 characters each).'''
        : '''Generate 40 tips as a JSON array, with exactly 10 tips for each of the following types: "fire_tips", "flood_tips", "safety_tips", "other_tips". Output only a raw JSON array.''';

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
You are an AI assistant that provides safety tips. You must respond only with a valid JSON array. Do not include any explanations, markdown formatting, or extra text.

Each element in the array should follow this structure:

{
  "content": "Tip content here",
  "type": "fire_tips" | "flood_tips" | "safety_tips" | "other_tips"
}
"""
          },
          {
            'role': 'user',
            'content': userPrompt,
          },
        ],
        'max_tokens': 1000,
        'temperature': 0.0,
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
          final aiTips =
              parsedTips.map((tip) => TipModel.fromMap(tip)).toList();

          // If description was not provided, persist the large default set.
          if (description == null || description.trim().isEmpty) {
            if (!await tipsService.store(aiTips)) {
              throw Exception('Failed to store tips.');
            }
          }

          return aiTips;
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
  Future<List<TipModel>> getStoredTips() async {
    return await tipsService.get();
  }
}
