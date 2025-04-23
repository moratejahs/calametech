import 'dart:io';
import 'dart:convert';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/exceptions/validation_exception.dart';
import 'package:calamitech/core/utils/helpers/parse_laravel_validation_errors.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/core/utils/services/sos_service.dart';
import 'package:calamitech/features/report/models/sos_model.dart';
import 'package:calamitech/features/report/repositories/i_sos_repository.dart';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';

class SosRepository extends ISosRepository {
  final http.Client httpClient;
  final AuthUserService authUserService;
  final SosService sosService;

  SosRepository({
    required this.httpClient,
    required this.authUserService,
    required this.sosService,
  });

  @override
  Future<void> store(
    String description,
    String type,
    File? image,
    String lat,
    String long,
  ) async {
    final user = await authUserService.get();
    if (user == null) throw Exception('No user data found.');
    final sos = await getFromStorage();

    if (sos == null) {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse(ApiPaths.report),
      );

      // Add headers
      request.headers.addAll({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ${user.token}',
      });

      // Add form fields
      request.fields['description'] = description;
      request.fields['type'] = type;
      request.fields['lat'] = lat;
      request.fields['long'] = long;

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
      }

      // Send request
      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      final jsonBody = jsonDecode(response.body);

      if (response.statusCode == 201) {
        if (!await sosService.store(SosModel.fromMap(jsonBody['data']['sos']))) {
          throw Exception('Failed to store sos data.');
        }
      } else if (response.statusCode == 422) {
        throw ValidationException(jsonBody['message'] ?? 'Failed to submit report.');
      } else {
        throw Exception(jsonBody['message'] ?? 'Failed to submit report.');
      }
    } else {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse('${ApiPaths.report}/${sos.id}'),
      );

      // Add headers
      request.headers.addAll({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ${user.token}',
      });

      // Add form fields
      request.fields['_method'] = 'put';
      request.fields['description'] = description;
      request.fields['type'] = type;
      request.fields['lat'] = lat;
      request.fields['long'] = long;

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
      }

      // Send request
      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      final jsonBody = jsonDecode(response.body);

      if (response.statusCode == 201) {
        final sos = SosModel.fromMap(jsonBody['data']['sos']);

        if (sos.status != 'pending') {
          await sosService.delete();
        } else {
          await sosService.store(SosModel.fromMap(jsonBody['data']['sos']));
        }
      } else if (response.statusCode == 422) {
        throw ValidationException(parseLaravelValidationErrors(jsonBody['errors']));
      } else {
        throw Exception(jsonBody['message'] ?? 'Failed to submit report.');
      }
    }
  }

  @override
  Future<SosModel?> getFromStorage() async {
    return await sosService.get();
  }

  @override
  Future<bool> storeInStorage(SosModel sos) async {
    return await sosService.store(sos);
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
