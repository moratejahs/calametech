import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/utils/services/rest_api_service.dart';

class SOSRepository {
  final RestApiService restApiService;

  const SOSRepository({
    required this.restApiService,
  });

  Future<Map<String, dynamic>> sendSOS(String token, double lat, double long) async {
    try {
      final response = await restApiService.post(
        ApiPaths.sos,
        {'lat': lat, 'long': long},
        token: token,
      );

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
