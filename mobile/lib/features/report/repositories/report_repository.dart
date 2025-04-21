import 'dart:io';
import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:flutter/rendering.dart';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart'; // Add this import for MediaType

class ReportRepository {
  final http.Client httpClient;
  final AuthUserService authUserService;

  ReportRepository({
    required this.httpClient,
    required this.authUserService,
  });

  Future<Map<String, dynamic>> submitReport(int? sosId, String description,
      String type, File? image, String token) async {
    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse("${ApiPaths.baseUrl}${ApiPaths.report}"),
      );

      // Add headers
      request.headers.addAll({
        'Authorization': 'Bearer $token',
      });

      // Add form fields
      request.fields['sos_id'] = sosId.toString();
      request.fields['description'] = description;
      request.fields['type'] = type;

      // Attach image if available
      if (image != null) {
        final fileName = image.path.split('/').last;
        final bytes = await image.readAsBytes();
        final file = http.MultipartFile.fromBytes(
          'image',
          bytes,
          filename: fileName,
          contentType: _getContentType(fileName),
        );
        request.files.add(file);
        debugPrint('Adding image: ${image.path}, size: ${bytes.length} bytes');
      }

      // Send request
      var streamedResponse = await request.send();
      var response = await http.Response.fromStream(streamedResponse);

      debugPrint('Response status: ${response.statusCode}');
      debugPrint('Response body: ${response.body}');

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      }

      throw Exception('Failed to submit report.');
    } catch (e) {
      debugPrint('ERROR: $e');
      rethrow;
    }
  }

  // Helper method to determine content type based on file extension
  MediaType _getContentType(String fileName) {
    final extension = fileName.split('.').last.toLowerCase();
    switch (extension) {
      case 'jpg':
      case 'jpeg':
        return MediaType('image', 'jpeg');
      case 'png':
        return MediaType('image', 'png');
      case 'gif':
        return MediaType('image', 'gif');
      default:
        return MediaType('application', 'octet-stream');
    }
  }
}
