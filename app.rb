require 'sinatra'
require 'sinatra/reloader'
require_relative 'models/word_finder'

set :erb, :trim => '-'

get '/' do
  texts = params['texts'] || ''
  matrix = texts.split(/,/).map{|row|row.split(//)}
  lengths = (params['lengths'] || []).map(&:to_i)
  nouse_dict = params['nouse_dict']=='1'
  results = []
  erb :index, locals: { texts: texts, lengths: lengths, nouse_dict: nouse_dict, results: results }
end
