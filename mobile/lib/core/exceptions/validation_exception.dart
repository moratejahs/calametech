class ValidationException implements Exception {
  final Map<String, List<String>> errors;

  ValidationException(this.errors);
}
