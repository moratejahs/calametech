import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/utils/services/rest_api_service.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/foundation.dart';

class SOSRepository {
  final RestApiService restApiService;
  final SecureStorageService storage;

  const SOSRepository({
    required this.restApiService,
    required this.storage,
  });

  Future<Map<String, dynamic>> sendSOS(double lat, double long) async {
    try {
      final token = await storage.readValue('token');

      if (token == null) {
        throw Exception('Token not found');
      }

      final response = await restApiService.post(
        ApiPaths.sos,
        {'lat': lat, 'long': long},
        token: token,
      );

      debugPrint('response: $response');

      if (response.containsKey('errors')) {
        return {'errors': response['errors']};
      }

      if (response.containsKey('success')) {
        return {
          'sos': response['sos'],
          'success': response['success'],
        };
      }

      throw Exception('Unexpected Format');
    } catch (e) {
      rethrow;
    }
  }

  // TODO: add update sos status method
}
