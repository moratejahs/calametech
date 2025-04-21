import 'package:calamitech/core/utils/services/secure_storage_service.dart';
import 'package:calamitech/features/report/models/sos_model.dart';

class SosService {
  final SecureStorageService storage;

  SosService({required this.storage});

  Future<SosModel?> get() async {
    final String? sosString = await storage.readValue('sos');

    if (sosString == null) return null;

    return SosModel.fromJson(sosString);
  }

  Future<bool> store(SosModel sos) async {
    return await storage.writeValue('sos', sos.toJson());
  }

  Future<bool> delete() async {
    return await storage.deleteValue('sos');
  }
}
