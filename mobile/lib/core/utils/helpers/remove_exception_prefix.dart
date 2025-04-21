String removeExceptionPrefix(String string) {
  if (string.startsWith('Exception: ')) {
    return string.substring(11);
  }
  return string;
}