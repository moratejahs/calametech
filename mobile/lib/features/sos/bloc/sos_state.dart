// part of 'sos_bloc.dart';
//
// @immutable
// sealed class SosState extends Equatable {
//   const SosState();
// }
//
// final class SosInitial extends SosState {
//   @override
//   List<Object> get props => [];
// }
//
// final class SosLoading extends SosState {
//   @override
//   List<Object> get props => [];
// }
//
// final class SosSuccess extends SosState {
//   final SOS sos;
//
//   const SosSuccess(
//     this.sos,
//   );
//
//   @override
//   List<Object> get props => [sos];
// }
//
//
// final class SosFailure extends SosState {
//   final String? message;
//   final Map<String, dynamic>? errors;
//
//   const SosFailure({
//     this.message,
//     this.errors,
//   });
//
//   @override
//   List<Object> get props => [
//     if (message != null) message!,
//     if (errors != null) errors!,
//   ];
// }